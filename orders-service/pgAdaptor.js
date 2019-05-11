require('dotenv').config()
const pgPromise = require('pg-promise');
const QueryFile = require('pg-promise').QueryFile;
const pgp = pgPromise({});

const config = {
  host: process.env.POSTGRES_HOST,
  port: process.env.POSTGRES_PORT,
  database: process.env.POSTGRES_DB,
  user: process.env.POSTGRES_USER,
  password: process.env.POSTGRES_PASSWORD
};



const db = pgp(config);

/*
db.none(QueryFile('./DbScripts/createTables.sql', { minify: true }))
  .then(data => {
    console.log('successfully created');
    initDB()
  })
  .catch(error => {
    console.log('error 1');
    console.log(error);
  });


  let initDB =() => {
    db.none(QueryFile('./DbScripts/fillTables.sql', { minify: true }))
    .then(data => {
      console.log('successfully filled');
    })
    .catch(error => {
      console.log('error 2');
      console.log(error);
    });
  }
  */


exports.db = db;