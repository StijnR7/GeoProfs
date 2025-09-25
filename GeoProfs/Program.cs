using System;
using GeoProfs;
using Npgsql;

class Program
{
    static void Main()
    {
        var connString = "Host=ep-round-morning-agqjwkbq-pooler.c-2.eu-central-1.aws.neon.tech;" +
                 "Username=neondb_owner;" +
                 "Password=npg_k4QlPfNz9pRX;" +
                 "Database=neondb;" +
                 "Ssl Mode=Require;" +
                 "Trust Server Certificate=true;";

        var conn = new NpgsqlConnection(connString);    
        conn.Open();

        Database database = new(conn);

        database.ShowUsers();
        
    }
}
