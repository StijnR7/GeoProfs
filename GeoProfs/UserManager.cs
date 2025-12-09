using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using static GeoProfs.UserManager;
using GeoProfs.Enums;
using MySqlConnector;
using System.Runtime.CompilerServices;
using GeoProfs.SessionData;
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


            List<Department> departments = database.GetDepartments();
            Console.WriteLine("Department?");
            for (int i = 0; i < departments.Count; i++) {
                Console.WriteLine($"[{i}]: {departments[i].Name}\n");
                
            }
            int choice1;
            bool success = int.TryParse(Console.ReadLine(), out choice1);
            if (!success) { return null; }
            Department chosenDepartment = departments[choice1];


           switch (role){
                case "employee":
                    role = "'[\"ROLE_USER\"]'";
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
                    user.Department = chosenDepartment.ToString();
                    
                    return user;
                    
                case "manager":
                    role = "'[\"ROLE_ADMIN\"]'";
                    IUser mUser = new ManagerUser(
                   defaultUserValues["FirstName"],
                   defaultUserValues["LastName"],
                   defaultUserValues["Email"],
                   defaultUserValues["Password"],
                   role,
                   int.Parse(defaultUserValues["Bsn"]),
                   DateTime.Parse(defaultUserValues["StartDate"])
                   




                   );
                    mUser.Department = chosenDepartment.ToString();
                    return mUser;
                   
                case "CEO":
                    role = "'[\"ROLE_ADMIN\"]'";
                    IUser cUser = new ManagerUser(
                  defaultUserValues["FirstName"],
                  defaultUserValues["LastName"],
                  defaultUserValues["Email"],
                  defaultUserValues["Password"],
                  role,
                  int.Parse(defaultUserValues["Bsn"]),
                  DateTime.Parse(defaultUserValues["StartDate"])





                  );
                    cUser.Department = chosenDepartment.ToString();
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
            SessionUser.audit_trail.SaveActionToAuditTrail("Created user");
            
        }
        public void DeleteUser() {
            Console.WriteLine("user id?");
            database.DeleteUser(int.Parse(Console.ReadLine()));
            SessionUser.audit_trail.SaveActionToAuditTrail("Deleted user");


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
            List<DisplayUser> user = database.getAllUsers();
            string filterWord = string.Empty;
            ConsoleKeyInfo key = new();
            List<DisplayUser> filteredUsers = new();
            bool searching = true;
            while (searching) {
                filteredUsers.Clear();
                for (int i = 0; i < user.Count; i++) {
                    if (user[i].FirstName.StartsWith(filterWord)){
                        Console.WriteLine($"{user[i].FirstName}");
                        filteredUsers.Add(user[i]);
                    }
                    
                }
                Console.WriteLine($"Current search: {filterWord}\n");
                key = Console.ReadKey(intercept: true);
                if (key.Key == ConsoleKey.Enter) { searching = false; }
                else if (key.Key == ConsoleKey.Backspace && filterWord.Length > 0) {
                    filterWord = filterWord.Remove(filterWord.Length - 1);
                
                }
                else { filterWord += key.KeyChar; }
            
            }

			for (int i = 0; i < filteredUsers.Count; i++)
			{
                Console.WriteLine("Filtered users:\n");
                Console.WriteLine($"[{i}] "+filteredUsers[i].FirstName + "\n");

			}

            Console.WriteLine("Choose user");
            int choice;
            while (true)
            {
                bool success = int.TryParse(Console.ReadLine(), out choice);
                if (success)
                {
                    return filteredUsers[choice];
                }
                
            }

        }
    }
}
