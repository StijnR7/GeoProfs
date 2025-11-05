using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using static GeoProfs.UserManager;
using GeoProfs.Enums;
using MySqlConnector;
using System.Runtime.CompilerServices;
namespace GeoProfs
{
    internal class UserManager
    {


        Database database;
        MySqlConnection conn;
       
        public UserManager(MySqlConnection conn) {
            this.conn = conn;
            this.database = new Database(conn);
            }

       
       
        private IUser CreateUserObject(string role) {
            Dictionary<string, string> defaultUserValues = askDefaultUserValues();
            




           switch (role){
                case "employee":
                    List<IUser> allManagerUsers = database.GetAllManagerUsers();
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

            database.SaveUserToDatabase(newUser);
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
        public DisplayUser ChooseUser() {
            List<DisplayUser> users = database.getAllUsers();
            string filterWord = string.Empty;
            ConsoleKeyInfo key = new();
            bool searching = true;
            while (searching) {
               
                for (int i = 0; i < users.Count; i++) {
                    if (users[i].FirstName.StartsWith(filterWord)){
                        Console.WriteLine($"{users[i].FirstName}");
                    }
                    
                }
                Console.WriteLine($"Current search: {filterWord}\n");
                key = Console.ReadKey(intercept: true);
                if (key.Key == ConsoleKey.Enter) { searching = false; }
                else { filterWord += key.KeyChar; }
            
            }

            return null;
        }
    }
}
