<?php

declare(strict_types=1);

namespace AndreFra96\Debugger;


/**
 * OVERVIEW: Le istanze di questa classe rappresentano blocchi di debugging del software
 * 
 * Il Debugger è rappresentato attraverso dei parametri di connessione ad un database, 
 * una connessione a tale database e un insieme di possibili test da effettuare 
 * 
 * Per preparare un Debugger all'esecuzione di test è necessario stabilire una connessione al database 
 * attraverso il metodo connect() che prende in input i parametri di connessione al db,
 * se questo metodo restituisce true possiamo proseguire con le operazioni.
 * E' possibile verificare lo stato attuale di connessione ad database attraverso il metodo connectionOK.
 * tests è un array (possibilmente vuoto) che rappresenta una mappa di questo tipo:
 * 
 * {
 * TESTID => ['loc_desc'=> ... , 'query' => ...],
 * TESTID => ['loc_desc'=> ... , 'query' => ...],
 * TESTID => ['loc_desc'=> ... , 'query' => ...]
 * }
 */
class Debugger
{

    private $conn;
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $tests;

    /**
     * Post-condizioni: inizializza un nuovo Debugger, non connesso ad alcun database e con l'array di test = []
     */
    function __construct()
    {
        $this->tests = [];
    }

    /**
     * Post-condizioni: restituisce un array contenente i test presenti nel Debugger
     */
    function tests()
    {
        return $this->tests;
    }

    /**
     * Post-condizioni:Effettua la connessione con il database attraverso i parametri indicati in input
     */
    function connect($servername, $username, $password, $dbname)
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) {
                return false;
            }

            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            $conn = new \mysqli($servername, $username, $password, $dbname);
        } catch (\ErrorException $e) {
            return false;
        }

        // Check connection
        if ($conn->connect_error) {
            return false;
        }

        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->conn = $conn;

        return true;
    }

    /**
     * Effetti-collaterali: potrebbe modificare this
     * Post-condizioni: legge il file il input e inserisce in $this->tests i test letti.
     *                  il formato di $this->tests è : [testid=>['query'=>... , 'loc_desc'=> ...], testid=>['query'=> ... , 'loc_desc' => ...], ... ]
     */
    function loadTestsFromFile($file)
    {
        require_once "exceptions/FileNotFoundException.php";
        $tests = [];
        try {
            $connection = fopen($file, "r");
            while (!feof($connection)) {
                $line = fgets($connection);
                $line = explode("|", $line);
                $id = $line[0];
                $line = [
                    'loc_desc' => $line[1],
                    'query' => $line[2]
                ];
                $tests[$id] = $line;
            }
        } catch (\ErrorException $e) {
            throw new FileNotFoundException("File non trovato o non formattato correttamente");
        } finally {
            fclose($connection);
        }
        $this->tests = $tests;
        return $tests;
    }

    /**
     * Post-condizioni: restituisce un array contente i parametri di connessione al database attuali del Debugger
     */
    function getParameter()
    {
        return [
            "username" => $this->username,
            "dbname" => $this->dbname,
            "password" => $this->password,
            "servername" => $this->servername,
        ];
    }

    /**
     * Post-condizioni: restituisce true se il debugger è in grado di connettersi al database, false altrimenti
     */
    function connectionOk()
    {
        if ($this->conn->connect_error)
            return false;
        return true;
    }

    /**
     * Post-condizioni: restituisce una progressbar verde se $status è true, rossa altrimenti
     */
    private function _progressBar($status)
    {
        $HTMLstring = '<div class="progress">';
        if ($status) {
            $HTMLstring .= '<div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"';
        } else {
            $HTMLstring .= '<div class="progress-bar bg-danger" role="progressbar" aria-valuenow="100"';
        }
        $HTMLstring .= 'aria-valuemin="0" aria-valuemax="100" style="width:100%">' . '</div>' . '</div>';

        return $HTMLstring;
    }

    /**
     * Post-condizioni: restituisce una stringa corrispondente alla rappresentazione del test indicato in input sotto forma di riga di una tabella
     */
    function asTableRow($testid)
    {
        $returnString = "<tr>";
        if ($this->tests[$testid]) {
            $test = $this->tests[$testid];
            $returnString .= "<th scope='row'>" . $testid . "</th>";
            $returnString .= "<td>" . $test['loc_desc'] . "</td>";
            $returnString .= $this->debugSpecific($testid) ?
                "<td>" . $this->_progressBar(true) . "</td>" . "<td></td>" :
                "<td>" . $this->_progressBar(false) . "</td>" . "<td>" . '<span data-toggle="tooltip" data-placement="right" title="Visualizza errori"><i class="fas fa-bug" style="cursor:pointer;" data-toggle="modal" data-target="#test' . $testid . '"></i></span>' . "</td>";
        }
        return $returnString . "</tr>";
    }

    /**
     * Post-condizioni: restituisce true se la query di ricerca errori restituisce zero righe, false altrimenti
     */
    function debugSpecific($testid)
    {
        $result = $this->conn->query($this->tests[$testid]['query']);
        if (($result->num_rows) == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Post-condizioni: restituisce le righe corrispondenti agli errori sotto forma di array, 
     * se non ci sono errori restituisce un array vuoto
     */
    function debugData($testid)
    {
        $data = [];
        $result = $this->conn->query($this->tests[$testid]['query']);
        while ($line = $result->fetch_assoc()) {
            array_push($data, $line);
        }
        return $data;
    }

    

    /**
     * - Effetti-collaterali: Potrebbe modificare lo stato di this, aggiornando lo stato dei diversi attributi in base alla loro validità attuale
     * - Post-condizioni: modifica gli attributi di this in base alla risposta delle funzioni di controllo 
     * e restituisce un array contenente lo stato attuale
     */
    function debug()
    {
        // $this->orderStatus = $this->checkOrder();
        // $this->itemsStatus = $this->checkItems();
        // $this->renewStatus = $this->checkRenew();
        // $this->renewItemsStatus = $this->checkRenewItems();
        // $this->monthlyStatus = $this->checkMonthly();
        // $this->serialStatus = $this->checkSerial();
        // $this->customerStatus = $this->checkCustomer();
        // $this->locationStatus = $this->checkLocation();
        // $this->groupStatus = $this->checkGroup();
        // return $this->getStatus();
    }


    function __toString()
    {
        $returnString = "Connessione al db " . ($this->connectionOk() ? "avvenuta con successo" : "non riuscita");
        $returnString .= "Stato ordini: " . ($this->orderStatus ? "Ok" : "Error");
        $returnString .= ", Stato items: " . ($this->itemsStatus ? "Ok" : "Error");
        $returnString .= ", Stato rinnovi: " . ($this->renewStatus ? "Ok" : "Error");
        $returnString .= ", Stato mensili: " . ($this->monthlyStatus ? "Ok" : "Error");
        $returnString .= ", Stato seriali: " . ($this->serialStatus ? "Ok" : "Error");
        $returnString .= ", Stato clienti: " . ($this->customerStatus ? "Ok" : "Error");
        $returnString .= ", Stato locali: " . ($this->locationStatus ? "Ok" : "Error");
        $returnString .= ", Stato gruppi: " . ($this->groupStatus ? "Ok" : "Error");
        return $returnString;
    }
}
