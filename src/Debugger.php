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
    private $renewItemsStatus;
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
     * Post-condizioni: restituisce un array contenente le informazioni attuali sugli stati, es: [orderStatus=>true,itemStatus=>true...]
     */
    function getStatus()
    {
        return [
            "orderStatus" => $this->orderStatus,
            "itemsStatus" => $this->itemsStatus,
            "renewStatus" => $this->renewStatus,
            "renewItemsStatus" => $this->renewItemsStatus,
            "monthlyStatus" => $this->monthlyStatus,
            "serialStatus" => $this->serialStatus,
            "customerStatus" => $this->customerStatus,
            "locationStatus" => $this->locationStatus,
            "groupStatus" => $this->groupStatus
        ];
    }

    /**
     * - Effetti-collaterali: Potrebbe modificare lo stato di this, aggiornando lo stato dei diversi attributi in base alla loro validità attuale
     * - Post-condizioni: modifica gli attributi di this in base alla risposta delle funzioni di controllo 
     * e restituisce un array contenente lo stato attuale
     */
    function debug()
    {
        $this->orderStatus = $this->checkOrder();
        $this->itemsStatus = $this->checkItems();
        $this->renewStatus = $this->checkRenew();
        $this->renewItemsStatus = $this->checkRenewItems();
        $this->monthlyStatus = $this->checkMonthly();
        $this->serialStatus = $this->checkSerial();
        $this->customerStatus = $this->checkCustomer();
        $this->locationStatus = $this->checkLocation();
        $this->groupStatus = $this->checkGroup();
        return $this->getStatus();
    }

    /**
     * Post-condizioni: $orderStatus diventa true se le verifiche sugli ordini danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkOrder()
    {

        $result = $this->conn->query("SELECT count(*)
        FROM t_order
        LEFT JOIN t_order_item
        ON t_order.order_id = t_order_item.order_id
        WHERE t_order_item.order_id IS NULL");
        if (($result->fetch_array())['count(*)'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Post-condizioni: restituisce un array contenente gli order_id degli ordini senza articoli collegati
     *                  solleva FalseQueryException se la query di ricerca restituisce false
     */
    function orderErrors()
    {
        $orders = [];
        if ($result = $this->conn->query("SELECT t_order.order_id
        FROM t_order
        LEFT JOIN t_order_item
        ON t_order.order_id = t_order_item.order_id
        WHERE t_order_item.order_id IS NULL")) {
            while ($line = $result->fetch_array()) {
                array_push($orders, $line['order_id']);
            }
        } else {
            throw new \AndreFra96\Debugger\FalseQueryException();
        }
        return $orders;
    }

    /**
     * Effetti-collaterali: effettua delle modifiche alla tabella t_order del database
     * Post-condizioni: elimina tutti i record della t_order restituiti dalla funzione orderErrors()
     *                  solleva FalseQueryException se la query restituisce false
     */
    function repairOrder()
    {
        $errors = $this->orderErrors();
        foreach ($errors as $index => $value) {
            if (!($this->conn->query("DELETE FROM t_order WHERE order_id = " . $value))) {
                throw new \AndreFra96\Debugger\FalseQueryException();
            }
        }
    }

    /**
     * Post-condizioni: $itemsStatus diventa true se le verifiche sugli items danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkItems()
    {
        $result = $this->conn->query("SELECT count(*)
        FROM t_order_item
        LEFT JOIN t_order
        ON t_order_item.order_id = t_order.order_id
        WHERE t_order.order_id IS NULL");
        if (($result->fetch_array())['count(*)'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Post-condizioni: restituisce un array contenente gli item_id degli articoli senza ordine collegato
     *                  solleva FalseQueryException se la query di ricerca restituisce false
     */
    function itemsErrors()
    {
        $items = [];
        if ($result = $this->conn->query("SELECT t_order_item.item_id
        FROM t_order_item
        LEFT JOIN t_order
        ON t_order_item.order_id = t_order.order_id
        WHERE t_order.order_id IS NULL")) {
            while ($line = $result->fetch_array()) {
                array_push($items, $line['item_id']);
            }
        } else {
            throw new \AndreFra96\Debugger\FalseQueryException();
        }
        return $items;
    }

    /**
     * Effetti-collaterali: effettua delle modifiche alla tabella t_order del database
     * Post-condizioni: elimina tutti i record della t_order_item restituiti dalla funzione itemsErrors()
     *                  solleva FalseQueryException se la query restituisce false
     */
    function repairItems()
    {
        $errors = $this->itemsErrors();
        foreach ($errors as $index => $value) {
            if (!($this->conn->query("DELETE FROM t_order_item WHERE item_id = " . $value))) {
                throw new \AndreFra96\Debugger\FalseQueryException();
            }
        }
    }


    /**
     * Post-condizioni: $renewStatus diventa true se le verifiche sui rinnovi danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkRenew()
    {
        $result = $this->conn->query("SELECT count(*)
        FROM t_rinnovi
        LEFT JOIN t_rinnovi_items
        ON t_rinnovi.rinnovo_id = t_rinnovi_items.rinnovo_id
        WHERE t_rinnovi_items.rinnovo_id IS NULL");
        if (($result->fetch_array())['count(*)'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Post-condizioni: $renewStatus diventa true se le verifiche sui rinnovi danno esito positivo (Non vengono trovati errori), false altrimenti
     */
    function checkRenewItems()
    {
        return false;
    }

    /**
     * Post-condizioni: restituisce un array contenente gli rinnovo_id dei rinnovi senza articoli collegati
     *                  solleva FalseQueryException se la query di ricerca restituisce false
     */
    function renewErrors()
    {
        $renews = [];
        if ($result = $this->conn->query("SELECT t_rinnovi.rinnovo_id
        FROM t_rinnovi
        LEFT JOIN t_rinnovi_items
        ON t_rinnovi.rinnovo_id = t_rinnovi_items.rinnovo_id
        WHERE t_rinnovi_items.rinnovo_id IS NULL")) {
            while ($line = $result->fetch_array()) {
                array_push($renews, $line['rinnovo_id']);
            }
        } else {
            throw new \AndreFra96\Debugger\FalseQueryException();
        }
        return $renews;
    }

    /**
     * Effetti-collaterali: effettua delle modifiche alla tabella t_rinnovi del database
     * Post-condizioni: elimina tutti i record della t_rinnovi restituiti dalla funzione renewErrors()
     *                  solleva FalseQueryException se la query restituisce false
     */
    function repairRenew()
    {
        $errors = $this->renewErrors();
        foreach ($errors as $index => $value) {
            if (!($this->conn->query("DELETE FROM t_rinnovi WHERE rinnovo_id = " . $value))) {
                throw new \AndreFra96\Debugger\FalseQueryException();
            }
        }
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
