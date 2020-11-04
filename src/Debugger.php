<?php

declare(strict_types=1);

namespace AndreFra96\Debugger;

/**
 * OVERVIEW: Le istanze di questa classe rappresentano blocchi di debugging del software
 * 
 * Funzione di astrazione: Gli attributi della classe rappresentano lo stato attuale della porzione di programma a cui si riferiscono, 
 * un valore true rappresenta il corretto funzionamento della sezione un valore false invece evidenzia delle criticità nella sezione
 * 
 * Invariante di rappresentazione: Gli attributi della classe assumono solamente valori booleani, 
 * fatta eccezione per l'attributo relativo al file di connessione con il database
 */
class Debugger
{
    private $orderStatus;
    private $itemsStatus;
    private $renewStatus;
    private $monthlyStatus;
    private $serialStatus;
    private $customerStatus;
    private $locationStatus;
    private $groupStatus;
    private $conn;
    private $servername;
    private $username;
    private $password;
    private $dbname;

    /**
     * Post-condizioni:Effettua la connessione con il database attraverso i parametri indicati in input
     * ,solleva InvalidArgumentException se la connessione al database fallisce
     */
    function connect($servername, $username, $password, $dbname)
    {
        $conn = new \mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new \InvalidArgumentException("Parametri di connessione non validi, impossibile stabilire la connessione con il database");
        }

        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->conn = $conn;
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
     * Effetti-collaterali: Potrebbe modificare lo stato di this, aggiornando lo stato dei diversi attributi in base alla loro validità attuale
     * Post-condizioni: modifica gli attributi di this in base alla risposta delle funzioni di controllo
     */
    function debug()
    {
        $this->orderStatus = $this->checkOrder();
        $this->itemsStatus = $this->checkItems();
        $this->renewStatus = $this->checkRenew();
        $this->monthlyStatus = $this->checkMonthly();
        $this->serialStatus = $this->checkSerial();
        $this->customerStatus = $this->checkCustomer();
        $this->locationStatus = $this->checkLocation();
        $this->groupStatus = $this->checkGroup();
    }

    /**
     * Post-condizioni: $orderStatus diventa true se le verifiche sugli ordini danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkOrder()
    {
        return false;
    }

    /**
     * Post-condizioni: $itemsStatus diventa true se le verifiche sugli items danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkItems()
    {
        return false;
    }

    /**
     * Post-condizioni: $renewStatus diventa true se le verifiche sui rinnovi danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkRenew()
    {
        return false;
    }

    /**
     * Post-condizioni: $monthlyStatus diventa true se le verifiche sui mensili danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkMonthly()
    {
        return false;
    }

    /**
     * Post-condizioni: $serialStatus diventa true se le verifiche sui seriali danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkSerial()
    {
        return false;
    }

    /**
     * Post-condizioni: $customerStatus diventa true se le verifiche sui clienti danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkCustomer()
    {
        return false;
    }

    /**
     * Post-condizioni: $locationStatus diventa true se le verifiche sui locali danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkLocation()
    {
        return false;
    }

    /**
     * Post-condizioni: $groupStatus diventa true se le verifiche sui gruppi danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkGroup()
    {
        return false;
    }

    /**
     * Post-condizioni: restituisce un array contenente le informazioni attuali sugli stati, es: [orderStatus=>true,itemStatus=>true...]
     */
    function getStatus()
    {
        return [
            "orderStatus" => $this->orderStatus,
            "itemsStatus" => $this->itemsStatus,
            "renewStatus" => $this->renewStatus,
            "monthlyStatus" => $this->monthlyStatus,
            "serialStatus" => $this->serialStatus,
            "customerStatus" => $this->customerStatus,
            "locationStatus" => $this->locationStatus,
            "groupStatus" => $this->groupStatus
        ];
    }

    function __toString()
    {
        $returnString = "Connessione al db " . $this->connectionOk() ? "avvenuta con successo" : "non riuscita";
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
