const graphql = require('graphql');

const { GraphQLObjectType, GraphQLString, GraphQLID } = graphql;

const OrderType = new GraphQLObjectType({
  name: 'orders',
  type: 'Query',
  fields: {
    id: { type: GraphQLID },
    userid: { type: GraphQLString },
    productid: { type: GraphQLString },
    issuedate: { type: GraphQLString },
  },
});

exports.OrderType = OrderType;
