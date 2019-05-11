const graphql = require('graphql');
const { db } = require('../pgAdaptor');

const { GraphQLObjectType, GraphQLString } = graphql;
const { OrderType } = require('./types');
const jwt = require('jsonwebtoken');
const ProductModel = require('../models/product');

const KEY = 'compratec';

const RootMutation = new GraphQLObjectType({
  name: 'RootMutationType',
  type: 'Mutation',
  fields: {
    addOrder: {
      type: OrderType,
      args: {
        token: { type: GraphQLString },
        productId: { type: GraphQLString },
      },
      resolve(parentValue, args) {
        const legit = jwt.verify(args.token, KEY);
        console.log(`\nJWT verification result: ${JSON.stringify(legit)}`);
        const decoded = jwt.decode(args.token, { complete: true });
        console.log(`\nDecoded jwt: ${JSON.stringify(decoded)}`);
        const userFromToken = decoded.payload.data.userId;
        return new Promise((resolve, reject) => {
          ProductModel.countDocuments({ code: args.productId }, (err, docs) => {
            console.log(docs);
            if (docs > 0) {
              const query = 'INSERT INTO orders( userId, productId ) VALUES ($1,$2) RETURNING id, userId, productId, issueDate';
              const values = [
                userFromToken,
                args.productId,
              ];

              resolve(db
                .one(query, values)
                .then(res => res)
                .catch(err => err));
            } else {
              reject({ err: 'Error, product id not valid' });
            }
          });
        });
      },
    },
  },
});


exports.mutation = RootMutation;
