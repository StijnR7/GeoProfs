using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using GeoProfs.SessionData;
using MySqlConnector;
namespace GeoProfs
{
    public class Audit_trail
    {
        Database database;
        MySqlConnection conn;
        public Audit_trail(MySqlConnection conn)
        {
            this.conn = conn;
            this.database = new Database(conn);
        }
        public void SaveActionToAuditTrail(string action) {
            int user_id = SessionUser.sessionUser.ID;
            DateTime dateTime = DateTime.Now;
            database.SaveAudit(user_id, action, dateTime);

        
        
        }
        public void ShowAuditTrailData()
        {
            List<AuditItem> auditItems = database.getAuditItems();
            for (int i = 0; i < auditItems.Count; i++)
            {
                Console.WriteLine($"ID: {auditItems[i].id} | User ID: {auditItems[i].user_id} | Action: {auditItems[i].action} | Date/Time: {auditItems[i].date_time}");
            }
        }
    }
}
