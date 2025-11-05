using GeoProfs.Enums;
using MySqlConnector;
using MySqlX.XDevAPI.Relational;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

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

        // [T] 
        public List<LeaveRequest> GetAllLeaveRequests()
        {
            var leaveRequests = new List<LeaveRequest>();

            string query = $"SELECT * FROM `{DatabaseEnums.DatabaseTables.leave}`;";
            using (var cmd = new MySqlCommand(query, conn))
            using (var reader = cmd.ExecuteReader())
            {
                while (reader.Read())
                {
                    var req = new LeaveRequest(
                        int.Parse(reader["userID"].ToString()),
                        DateTime.Parse(reader["leaveStart"].ToString()),
                        DateTime.Parse(reader["leaveEnd"].ToString())
                    );
                    bool success = Enum.TryParse(reader["status"].ToString(), out LeaveEnums.LeaveStatus currentStatus);
                    if (success) { req.Status = currentStatus; }
                    int leaveID;
                    if (!int.TryParse(reader["id"].ToString(), out leaveID))
                    {
                        Console.WriteLine("No valid id found");
                    }
                    else {
                        req.Id = leaveID;
                    }

                    {

                    }
                    leaveRequests.Add(req);
                }
            }

            return leaveRequests;
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
        public List<DisplayUser> getAllUsers()
        {
            List < DisplayUser > users = new();
            string query = "SELECT * FROM `users`;";

            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {


                while (reader.Read())
                {
                    DisplayUser user = new DisplayUser(
                        reader["firstName"].ToString(),
                        reader["lastName"].ToString(),
                        reader["email"].ToString(),
                        reader["password"].ToString(),
                        reader["position"].ToString(),
                        int.Parse(reader["bsn"].ToString()),
                        DateTime.Parse(reader["startDate"].ToString()),
                        int.Parse(reader["leaveDaysPerYear"].ToString())

                     



                        );
                    user.ID = int.Parse(reader["id"].ToString());
                    users.Add(user);

                }
            }
            return users;

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
        public DisplayUser GetUserFromID(int userID) {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();
            string query = $"SELECT *\r\nFROM users\r\nWHERE id = '{userID}';";
            DisplayUser displayUser;
            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {


                while (reader.Read())
                {
                    if (reader["position"].ToString() != "employee") { continue; }
                    DisplayUser user = new DisplayUser(
                          reader["firstName"].ToString(),
                        reader["lastName"].ToString(),
                        reader["email"].ToString(),
                        reader["password"].ToString(),
                        reader["position"].ToString(),
                        int.Parse(reader["bsn"].ToString()),
                        DateTime.Parse(reader["startDate"].ToString()),
                        int.Parse(reader["leaveDaysPerYear"].ToString())


                        );
                    
                    return user;

                }
            }
            return null;
            
        
        }
        public void ChangeLeaveStatus(int leaveID, LeaveEnums.LeaveStatus newStatus) {

            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();
            var cmd = new MySqlCommand($@"UPDATE `{DatabaseEnums.DatabaseTables.leave}` SET `status` = '{newStatus}' WHERE `id` = '{leaveID}';", conn);

            cmd.ExecuteNonQuery();

        }


        public void AddShift(int userID, UserEnums.UserPositions position, DateOnly shiftDate,  TimeOnly startTime, TimeOnly endTime) {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();
            var cmd = new MySqlCommand($@"
            INSERT INTO {DatabaseEnums.DatabaseTables.shifts} 
            (userID, startTime, endTime, position, shiftDate) 
            VALUES 
            ('{userID}',
             '{startTime}',
             '{endTime}',
             '{position}',
             '{shiftDate:yyyy-MM-dd}'
            );
        ", conn);

            cmd.ExecuteNonQuery();

        }


    }
}
