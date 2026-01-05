using System;
using MySqlConnector;

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

        public void AddNewShift()
        {
            Console.WriteLine("User ID?");
            int userId;
            if (!int.TryParse(Console.ReadLine(), out userId)) return;

            Console.WriteLine("Shift date (yyyy-mm-dd)?");
            DateOnly shiftDate;
            if (!DateOnly.TryParse(Console.ReadLine(), out shiftDate)) return;

            Console.WriteLine("Start time (HH:mm)?");
            TimeOnly startTime;
            if (!TimeOnly.TryParse(Console.ReadLine(), out startTime)) return;

            Console.WriteLine("End time (HH:mm)?");
            TimeOnly endTime;
            if (!TimeOnly.TryParse(Console.ReadLine(), out endTime)) return;

            database.AddShift(userId, shiftDate, startTime, endTime);
        }
    }
}
