using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySqlConnector;
using GeoProfs.Enums;
namespace GeoProfs
{
    public class ShiftManager
    {
        Database database;
        MySqlConnection conn;
        public ShiftManager(MySqlConnection conn)
        {
            this.conn = conn;
            this.database = new Database(conn);
        }


        public void AddNewShift() { 
            
        
        
        }

    }
}
