"use strict";
const graphql = require("graphql");
const express = require("express");
const expressGraphQl = require("express-graphql");
const { GraphQLSchema } = graphql;
const { query } = require("./schemas/queries");
const { mutation } = require("./schemas/mutations");
const mongoose = require('mongoose');

const schema = new GraphQLSchema({
  query,
  mutation
});

var app = express();
app.use(
  '/',
  expressGraphQl({
    schema: schema
  })
);

mongoose.connect('mongodb://root:compratec@mongodb-primary:27017/admin')
mongoose.connection.once('open', () => {
  console.log('conneted to mongo database');
});

app.listen(80, () =>
  console.log('GraphQL server running on 0.0.0.0:80')
);
