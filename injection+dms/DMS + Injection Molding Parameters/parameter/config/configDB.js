const sql = require('mssql');

const config = {
    user: 'your_username',
    password: 'your_password',
    server: 'your_server', // e.g., localhost or IP address
    database: 'injection_molding',
    options: {
        encrypt: true, // Use this if you're on Azure
        enableArithAbort: true
    }
};

const poolPromise = new sql.ConnectionPool(config)
    .connect()
    .then(pool => {
        console.log('Connected to SQL Server');
        return pool;
    })
    .catch(err => {
        console.log('Database connection failed: ', err);
    });

module.exports = {
    sql, poolPromise
};
