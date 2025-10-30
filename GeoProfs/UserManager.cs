using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using static GeoProfs.UserManager;

using MySqlConnector;
namespace GeoProfs
{
    internal class UserManager
    {
       

            
        MySqlConnection conn;
       
        public UserManager(MySqlConnection conn) {
            this.conn = conn;
            }

       
       
        private IUser CreateUserObject(string role) {
            Dictionary<string, string> defaultUserValues = askDefaultUserValues();





           switch (role){
                case "employee":
                    List<IUser> allManagerUsers = GetAllManagerUsers();
                    Console.WriteLine("Supervisor?");
                    for (int i = 0; i < allManagerUsers.Count; i++) {
                        Console.WriteLine($"{i}: {allManagerUsers[i].FirstName}");
                    
                    }
                    int choice = int.Parse(Console.ReadLine());
                    int supervisor = allManagerUsers[choice].ID;
                    IUser user = new EmployeeUser(
                        defaultUserValues["FirstName"], 
                        defaultUserValues["LastName"], 
                        defaultUserValues["Email"],
                        defaultUserValues["Password"],
                        role,
                        int.Parse(defaultUserValues["Bsn"]),
                        DateTime.Parse(defaultUserValues["StartDate"]),
                        supervisor




                        );
                    return user;
                    
                case "manager":
                    IUser mUser = new ManagerUser(
                   defaultUserValues["FirstName"],
                   defaultUserValues["LastName"],
                   defaultUserValues["Email"],
                   defaultUserValues["Password"],
                   role,
                   int.Parse(defaultUserValues["Bsn"]),
                   DateTime.Parse(defaultUserValues["StartDate"])
                   




                   );
                    return mUser;
                   
                case "CEO":
                    IUser cUser = new ManagerUser(
                  defaultUserValues["FirstName"],
                  defaultUserValues["LastName"],
                  defaultUserValues["Email"],
                  defaultUserValues["Password"],
                  role,
                  int.Parse(defaultUserValues["Bsn"]),
                  DateTime.Parse(defaultUserValues["StartDate"])





                  );
                    return cUser;


            }
            

            return null;
        }
        public void CreateUser()
        {
            
            Console.WriteLine("Role?");
            string role = Console.ReadLine();
            IUser newUser = CreateUserObject(role);

            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();
            var cmd = new MySqlCommand($@"
            INSERT INTO {Database.DatabaseTables.users} 
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
        public List<IUser> GetAllManagerUsers() {
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
        public Dictionary<string, string> askDefaultUserValues() {
            Dictionary<string, string> defaultUserValues = new Dictionary<string, string>()
        {
            {"FirstName", null },
            {"LastName", null },
            {"Email", null },
            {"Password", null },
            {"Bsn", null },
            {"StartDate", null },
            {"LeaveDaysPerYear", null }


        };
            foreach (string key in defaultUserValues.Keys) {
                Console.WriteLine($"{key}?");
                defaultUserValues[key] = Console.ReadLine();
            
            }
            return defaultUserValues;


        }
    }
}
