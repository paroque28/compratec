const mongoose = require('mongoose');
const Schema = mongoose.Schema;

const ProductSchema = new Schema({
    code: {type:String},
    name: {type:String},
    color: {type:String},
    price: {type:Number},
    quantity: {type:Number}
});

module.exports = mongoose.model('Product', ProductSchema, 'product');

