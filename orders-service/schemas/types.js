const graphql = require("graphql");
const { GraphQLObjectType, GraphQLString, GraphQLID} = graphql;


const CustomerType = new GraphQLObjectType({
  name: "Customer",
  type: "Query",
  fields: {
    id: { type: GraphQLID },
    firstname: { type: GraphQLString },
    lastname: { type: GraphQLString },
    telephone: { type: GraphQLString }
  }
});

const OrderType = new GraphQLObjectType({
  name: "Order",
  type: "Query",
  fields: {
    id: { type: GraphQLID },
    postDate: { type: GraphQLString },
    description: { type: GraphQLString }
  }
});

const ProductType = new GraphQLObjectType({
  name: "Product",
  type: "Query",
  fields: {
    id: { type: GraphQLID },
    name: { type: GraphQLString },
    code: { type: GraphQLString }
  }
});

const OrderProductType = new GraphQLObjectType({
  name: "OrderProduct",
  type: "Query",
  fields: {
    id: { type: GraphQLID },
    productId: { type: GraphQLID },
    orderId: { type: GraphQLID }
  }
});

const CustomerOrderType = new GraphQLObjectType({
  name: "CustomerOrder",
  type: "Query",
  fields: {
    id: { type: GraphQLID },
    customerId: { type: GraphQLID },
    orderId: { type: GraphQLID }
  }
});

exports.CustomerType = CustomerType;
exports.OrderType = OrderType;
exports.ProductType = ProductType;
exports.OrderProductType = OrderProductType;
exports.CustomerOrderType = CustomerOrderType;