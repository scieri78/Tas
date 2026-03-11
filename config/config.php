<?php

abstract class absConfig
{
    protected $host;
    protected $user;
    protected $pass;
    protected $db;
    protected $port;
    protected $driver;


    /**
     * @return mixed
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param mixed $driver
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @return string
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param string $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @param string $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    public function getController($Controller)
    {
        $Controller = ($Controller != "statoshell") ? $Controller : "processing";
        $Controller = ($Controller != "workflow2") ? $Controller : "workflow";
        $Controller = ($Controller != "workflow2") ? $Controller : "workflow";
        $Controller = ($Controller != "searchcoll") ? $Controller : "Ricerca Colonne";
        return $Controller;
    }
}
