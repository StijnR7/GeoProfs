using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Npgsql;
using MySqlConnector;

namespace GeoProfs
{
    class Database
    {
        MySqlConnection conn;

        public Database(MySqlConnection conn) { 
            this.conn = conn;
            
        }
        public enum DatabaseTables { 
            users,
            leave
        }
       
        public void ShowAllDataFromTable(DatabaseTables table) {
            var cmd = new MySqlCommand($"SELECT * FROM {table}", conn);
            var reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                for (int i = 0; i < reader.FieldCount; i++)
                {
                    Console.Write($"{reader.GetName(i)}: {reader[i]}\n");
                }
                Console.WriteLine("\n");
            }
            
            

        }


    }
}
