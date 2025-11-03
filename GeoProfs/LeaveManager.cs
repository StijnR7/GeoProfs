using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySqlConnector;
using GeoProfs.Enums;
namespace GeoProfs
{
    public class LeaveManager
    {
        Database database;
        MySqlConnection conn;
        public LeaveManager(MySqlConnection conn)
        {
            this.conn = conn;
            this.database = new Database(conn);
        }
        public int SeeLeaveRequests(bool selectRequestID) {
            database.ShowAllDataFromTable(Enums.DatabaseEnums.DatabaseTables.leave);






            return 0;
        
        }
    }
}
