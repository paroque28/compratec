const { GraphQLObjectType, GraphQLID, GraphQLList } = require('graphql');
const { db } = require('../pgAdaptor');

const {
  OrderType,
} = require('./types');

const RootQuery = new GraphQLObjectType({
  name: 'RootQueryType',
  type: 'Query',
  fields: {
    order: {
      type: OrderType,
      args: { id: { type: GraphQLID } },
      resolve(args) {
        const query = 'SELECT * FROM orders WHERE id=$1';
        const values = [args.id];

        return db
          .one(query, values)
          .then(res => res)
          .catch(err => err);
      },
    },
    allOrders: {
      type: GraphQLList(OrderType),
      resolve() {
        const query = 'SELECT * FROM orders';

        return db
          .any(query)
          .then(res => res)
          .catch(err => err);
      },
    },

  },
});

exports.query = RootQuery;
