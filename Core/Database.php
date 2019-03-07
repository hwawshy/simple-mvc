<?php

   namespace Core;

   use PDO;
   use PDOException;

   class Database
   {
      protected $db_host = 'localhost';
      protected $db_name = 'blog';
      protected $db_user = 'root';
      protected $db_password = '';
      protected $db_options;

      protected $db_object;
      protected $db_statement;

      public $last_sql;

      public function __construct()
      {
         $this->connect();
      }

      protected function connect()
      {
         $this->db_options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
         $dsn = "mysql:host={$this->db_host};dbname={$this->db_name}";
         try
         {
            $this->db_object = new PDO($dsn, $this->db_user, $this->db_password, $this->db_options);
         }
         catch (PDOException $e)
         {
            die($e->getMessage());
         }
      }

      public function query($sql, $params = [])
      {
         $this->last_sql = $sql;
         if (!isset($this->db_object))
         {
            $this->connect();
         }

         if ($this->db_statement = $this->db_object->prepare($sql))
         {
            if (!$this->db_statement->execute($params))
            {
               $error = $this->db_statement->errorInfo();
               die($error[2] . '<br />' . $this->last_sql);
            }
         }
         else
         {
            $error = $this->db_object->errorInfo();
            die($error[2] . '<br />' . $this->last_sql);
         }
      }

      public function get_row()
      {

         return isset($this->db_statement) ? $this->db_statement->fetch(PDO::FETCH_ASSOC) : null;
      }

      public function get_all()
      {
         return isset($this->db_statement) ? $this->db_statement->fetchAll(PDO::FETCH_ASSOC) : null;
      }

      public function last_insert_id()
      {
         return isset($this->db_object) ? $this->db_object->lastInsertId() : null;
      }

      public function affected_rows()
      {
         return isset($this->db_statement) ? $this->db_statement->rowCount() : null;
      }


   }
