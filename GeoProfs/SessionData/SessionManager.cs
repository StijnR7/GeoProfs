using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySqlConnector;
using GeoProfs.Enums;
namespace GeoProfs.SessionData
{
    public class SessionManager
    {

        Database database;
        MySqlConnection conn;
        public SessionManager(MySqlConnection conn)
        {
            this.conn = conn;
            this.database = new Database(conn);
        }
        public bool Login()
        {
            Console.WriteLine("Email?");
            string? email = Console.ReadLine();

            Console.WriteLine("password?");
            string? password = Console.ReadLine();

            if (string.IsNullOrWhiteSpace(email) || string.IsNullOrWhiteSpace(password))
            {
                Console.WriteLine("User not admin or doesnt exist.");
                return false;
            }

            ManagerUser loginuser = database.GetManagerFromLoginCred(email, password);

            if (loginuser == null)
            {
                Console.WriteLine("User not admin or doesnt exist.");
                return false;
            }

            SessionUser.sessionUser = loginuser;
            SessionUser.sessionUser.ID = loginuser.ID;

            Console.WriteLine($"Logged in as: {loginuser.FirstName}");
            return true;
        }

    }
}
