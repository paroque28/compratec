const graphql = require("graphql");
const { GraphQLObjectType, GraphQLString, GraphQLID} = graphql;

const OrderType = new GraphQLObjectType({
  name: "Order",
  type: "Query",
  fields: {
    id: { type: GraphQLID },
    userId: { type: GraphQLID },
    productId: { type: GraphQLID },
    postDate: { type: GraphQLString }
  }
});

exports.OrderType = OrderType;
