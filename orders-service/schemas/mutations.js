const graphql = require("graphql");
const db = require("../pgAdaptor").db;
const { GraphQLObjectType, GraphQLString } = graphql;
const { OrderType} = require("./types");
const jwt   = require('jsonwebtoken');

const RootMutation = new GraphQLObjectType({
  name: "RootMutationType",
  type: "Mutation",
  fields: {
    addOrder: {
      type: OrderType,
      args: {
        token: { type: GraphQLString },
        productId: { type: GraphQLString }
      },
      resolve(parentValue, args) {
        var legit = jwt.verify(token, publicKEY, verifyOptions);
        console.log("\nJWT verification result: " + JSON.stringify(legit));
        var decoded =  jwt.decode(token, {complete: true});
        console.log("\nDecoded jwt: "+ JSON.stringify(decoded));
        let userFromToken = decoded.data.userName;

        const query = `INSERT INTO orders( user_id, product_id ) VALUES ($1,$2) RETURNING order_id, user_id, product_id, issueDate`;
        const values = [
          userFromToken,
          args.productId
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
