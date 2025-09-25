using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Npgsql;


namespace GeoProfs
{
    class Database
    {
        NpgsqlConnection conn;

        public Database(NpgsqlConnection conn) { 
            this.conn = conn;
            
        }
        public enum DatabaseTables { 
            users,
            leave,
            test
        }
       
        public void ShowAllDataFromTable(DatabaseTables table) {
            var cmd = new NpgsqlCommand($"SELECT * FROM {table}", conn);
            var reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                Console.WriteLine($"" +
                    $"\nID: {reader["id"]}, " +
                    $"\nUsername: {reader["username"]}" +
                    $"\nemail: {reader["email"]}" +
                    $"\nleave days left: {reader["leaveDaysLeft"]}");
            }
            
            

        }


    }
}
