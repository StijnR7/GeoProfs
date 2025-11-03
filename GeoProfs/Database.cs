using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using GeoProfs.Enums;
using MySqlConnector;

namespace GeoProfs
{
    class Database
    {
        MySqlConnection conn;

        public Database(MySqlConnection conn) { 
            this.conn = conn;
            
        }
        
       
       
        public void ShowAllDataFromTable(DatabaseEnums.DatabaseTables table) {
            var cmd = new MySqlCommand($"SELECT * FROM `{table}`;", conn);
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
        public List<IUser> GetAllManagerUsers()
        {
            List<IUser> managerUsers = new();
            string query = "SELECT *\r\nFROM users\r\nWHERE position = 'manager';";

            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {


                while (reader.Read())
                {
                    IUser managerUser = new ManagerUser(
                        reader["firstName"].ToString(),
                        reader["lastName"].ToString(),
                        reader["email"].ToString(),
                        reader["password"].ToString(),
                        reader["position"].ToString(),
                        int.Parse(reader["bsn"].ToString()),
                        DateTime.Parse(reader["startDate"].ToString())



                        );
                    managerUser.ID = int.Parse(reader["id"].ToString());
                    managerUsers.Add(managerUser);

                }
            }

            return managerUsers;
        }
        public List<LeaveRequest> GetLeaveRequests(string status) { 
            List<LeaveRequest> leaveRequests = new();
            string query = $"SELECT * FROM leave WHERE status = '{status}'";
            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {


                while (reader.Read())
                {
                    

                }
            }
            return null;
        }
        public void SaveUserToDatabase(IUser newUser) {


            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();
            var cmd = new MySqlCommand($@"
            INSERT INTO {DatabaseEnums.DatabaseTables.users} 
            (firstName, lastName, email, password, position, bsn, superVisor, startDate, leaveDaysPerYear) 
            VALUES 
            ('{newUser.FirstName}', 
             '{newUser.LastName}', 
             '{newUser.Email}', 
             '{newUser.Password}', 
             '{newUser.Position}', 
             {newUser.Bsn}, 
             '{newUser.SuperVisor}', 
             '{newUser.StartDate:yyyy-MM-dd}', 
             {newUser.LeaveDaysPerYear});
        ", conn);

            cmd.ExecuteNonQuery();

        }


    }
}
