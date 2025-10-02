using System;
using GeoProfs;
using Npgsql;
using MySqlConnector;
class Program
{
    static void Main()
    {
        var connString = "Server=q0t164.h.filess.io;Port=3305;" +
     "User Id=geoprofs_magicfind;" +
     "Password=24621c3ce4a7d2fd3aae4aafe468aebe432f5d82;" +
     "Database=geoprofs_magicfind;";



        using var conn = new MySqlConnection(connString);
        conn.Open();

        Database database = new(conn);

        database.ShowAllDataFromTable(Database.DatabaseTables.users);
        
    }
}
