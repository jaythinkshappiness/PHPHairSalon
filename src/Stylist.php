<?php
    class Stylist
    {
        private $name;
        private $id;

        function  __construct($name,$id=null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        //getters

        function getName()
        {
            return $this->name;
        }
        function getId()
        {
           return $this->id;
        }

        //setters

        function setName($new_name)
        {
           $this->name = (string) $new_name;
        }
        function setId($new_id)
        {
           $this->id = (string) $new_id;
        }

        function save()
        {
           $GLOBALS['DB']->exec("INSERT INTO stylists (name) VALUES ('{$this->getName()}')");
           $this->id = $GLOBALS['DB']->lastInsertId();

        }
        static function getAll()
        {
            $returned_stylists = $GLOBALS['DB']->query("SELECT * FROM stylists;");
            $stylists = array();
            foreach ($returned_stylists as $stylist)
            {
                $stylist_name = $stylist['name'];
                $stylist_id = $stylist['id'];

                $new_stylist = new Stylist($stylist_name,$stylist_id);

                array_push($stylists, $new_stylist);
            }
            return $stylists;
        }
        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM stylists;");
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM stylists WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM clients WHERE stylist_id = {$this->getId()};");
        }

        static function find($search_id)
        {
            $found_stylist = null;
            $stylists = Stylist::getAll();
            foreach ($stylists as $stylist)
            {
                if ($search_id == $stylist->getId())
                {
                    $found_stylist = $stylist;
                }
            }
            return $found_stylist;
        }
        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE stylists SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function getClient()
        {
            $get_client = array();
            $returned_clients = $GLOBALS['DB']->query("SELECT * FROM clients WHERE stylist_id = {$this->getId()};");
            foreach($returned_clients as $client) {
                $name = $client['name'];
                $stylist_id = $client['stylist_id'];
                $id = $client['id'];
                $new_client = new Client($name, $stylist_id, $id);
                array_push($get_client, $new_client);
            }
            return $get_client;
        }

    }

 ?>
