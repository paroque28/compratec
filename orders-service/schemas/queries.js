const { db } = require("../pgAdaptor");
const { GraphQLObjectType, GraphQLID, GraphQLList } = require("graphql");
const { CustomerType, OrderType, ProductType, OrderProductType, CustomerOrderType } = require("./types");

const RootQuery = new GraphQLObjectType({
  name: "RootQueryType",
  type: "Query",
  fields: {
    order: {
      type: OrderType,
      args: { id: { type: GraphQLID } },
      resolve(parentValue, args) {
        const query = `SELECT * FROM orders WHERE id=$1`;
        const values = [args.id];

        return db
          .one(query, values)
          .then(res => res)
          .catch(err => err);
      }
    },
    allOrders: {
      type: OrderType,
      resolve(parentValue, args) {
        const query = `SELECT * FROM orders`;

        return db
          .one(query)
          .then(res => res)
          .catch(err => err);
      }
    }

  }
});

exports.query = RootQuery;