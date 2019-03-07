<?php

   namespace Core;

   abstract class Model
   {
      protected static $db;
      protected static $db_columns = [];
      protected static $table_name;

      public static function set_database(Database $db)
      {
         self::$db = $db;
      }

      public static function find_by_sql($sql, $params = [])
      {
         self::$db->query($sql, $params);
         $rows = self::$db->get_all();

         if (!empty($rows))
         {
            $objects = [];
            foreach ($rows as $row)
            {
               $objects[] = static::instantiate($row);
            }

            return $objects;
         }

         return false;
      }

      public static function find_by_id($id)
      {
         $sql = "SELECT * FROM " . static::$table_name . " WHERE id = ? LIMIT 1";
         $object_array = static::find_by_sql($sql, array($id));
         return $object_array ? array_shift($object_array) : false;

      }

      protected static function instantiate($row)
      {
         $object = new static;
         foreach ($row as $key => $value)
         {
            if (property_exists($object, $key))
            {
               $object->$key = $value;
            }
         }

         return $object;
      }

      protected function create()
      {
         $attributes = $this->db_attributes();
         $count = count($attributes);

         $sql = "INSERT INTO " . static::$table_name . " (";
         $sql .= implode(', ', array_keys($attributes)) . ") ";
         $sql .= "VALUES (";

         for ($i = 1; $i <= $count; $i++)
         {
            if ($i == $count)
               $sql .= "?";
            else
               $sql .= "?, ";
         }

         $sql .= ")";

         self::$db->query($sql, array_values($attributes));
         if (self::$db->affected_rows())
         {
            $this->id = self::$db->last_insert_id();
         }

      }

      protected function update()
      {
         $attributes = $this->db_attributes();
         $fields = array_keys($attributes);

         $sql = "UPDATE " . static::$table_name . " SET ";
         $sql .= implode(' = ?, ', $fields);
         $sql .= " = ? WHERE id = ? LIMIT 1";

         $values = array_values($attributes);
         $values[] = $this->id;
         self::$db->query($sql, $values);
      }

      public function save()
      {
         if (isset($this->id))
         {
            $this->update();
         }
         else
         {
            $this->create();
         }
      }

      public function delete()
      {
         if (isset($this->id))
         {
            $sql = "DELETE FROM " . static::$table_name . " WHERE id = ? LIMIT 1";
            self::$db->query($sql, array($this->id));
         }
      }

      // TODO: Make it private
      public function db_attributes()
      {
         $attributes = [];

         foreach (static::$db_columns as $column)
         {
            if ($column == 'id') continue;
            if (property_exists($this, $column))
            {
               $attributes[$column] = $this->$column;
            }
         }

         return $attributes;
      }

   }
