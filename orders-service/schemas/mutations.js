const graphql = require('graphql');
const jwt = require('jsonwebtoken');
const { db } = require('../pgAdaptor');

const { GraphQLObjectType, GraphQLString } = graphql;
const { OrderType } = require('./types');
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
        jwt.verify(args.token, KEY);
        const decoded = jwt.decode(args.token, { complete: true });
        const userFromToken = decoded.payload.data.userId;
        return new Promise((resolve, reject) => {
          ProductModel.countDocuments({ code: args.productId }, (error, docs) => {
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
              reject(new Error('Error, product id not valid'));
            }
          });
        });
      },
    },
  },
});


exports.mutation = RootMutation;
