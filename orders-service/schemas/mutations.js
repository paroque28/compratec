const graphql = require("graphql");
const db = require("../pgAdaptor").db;
const { GraphQLObjectType, GraphQLID, GraphQLString, GraphQLInt } = graphql;
const { OrderType, OrderProductType, CustomerOrderType} = require("./types");

const RootMutation = new GraphQLObjectType({
  name: "RootMutationType",
  type: "Mutation",
  fields: {
    addOrder: {
      type: OrderType,
      args: {
        description: { type: GraphQLString }
      },
      resolve(parentValue, args) {
        const query = `INSERT INTO Orders( postDate, description ) VALUES (current_timestamp,$1) RETURNING id, postDate, description`;
        const values = [
          args.description
        ];
        return db
          .one(query, values)
          .then(res => res)
          .catch(err => err);
      }
    },
    addProductToOrder: {
      type: OrderProductType,
      args: {
        orderId: { type: GraphQLID },
        productId: { type: GraphQLID },

      },
      resolve(parentValue, args) {
        const query = `INSERT INTO OrderProducts(orderId, productId ) VALUES ($1, $2) RETURNING id, productId, orderId `;
        const values = [
          args.orderId,
          args.productId
        ];

        return db
          .one(query, values)
          .then(res => res)
          .catch(err => err);
      }
    },
    addOrderToCustomer: {
      type: CustomerOrderType,
      args: {
        customerId: { type: GraphQLID },
        orderId: { type: GraphQLID },

      },
      resolve(parentValue, args) {
        const query = `INSERT INTO CustomerOrders(customerId, orderId) VALUES ($1, $2) RETURNING id, customerId, orderId`;
        const values = [
          args.customerId,
          args.orderId
        ];
        return db
          .one(query, values)
          .then(res => res)
          .catch(err => err);
      }
    }
  }
});

exports.mutation = RootMutation;
