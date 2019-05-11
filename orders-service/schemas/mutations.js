const graphql = require("graphql");
const db = require("../pgAdaptor").db;
const { GraphQLObjectType, GraphQLString } = graphql;
const { OrderType} = require("./types");
const jwt   = require('jsonwebtoken');

const KEY="compratec"

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
        // var legit = jwt.verify(args.token, KEY);
        // console.log("\nJWT verification result: " + JSON.stringify(legit));
        // var decoded =  jwt.decode(args.token, {complete: true});
        // console.log("\nDecoded jwt: "+ JSON.stringify(decoded));
        // let userFromToken = decoded.payload.data.userId;

        
        const query = `INSERT INTO orders( userId, productId ) VALUES ($1,$2) RETURNING id, userId, productId, issueDate`;
        const values = [
          // userFromToken,
          args.token,
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
