# flask_graphene_mongo/schema.py
import graphene
from graphene.relay import Node
from graphene_mongo import MongoengineConnectionField, MongoengineObjectType
from models import Product as ProductModel
from models import ProductType as ProductTypeModel
from models import Catalog as CatalogModel

class Product(MongoengineObjectType):
    class Meta:
        model = ProductModel
        interfaces = (Node,)



class ProductType(MongoengineObjectType):
    class Meta:
        model = ProductTypeModel
        interfaces = (Node,)



class Catalog(MongoengineObjectType):
    class Meta:
        model = CatalogModel
        interfaces = (Node,)


class Query(graphene.ObjectType):
    node = Node.Field()
    catalo = graphene.Field(Catalog)
    productType = graphene.Field(ProductType)
    product = graphene.Field(Product)
    all_products = MongoengineConnectionField(Product)
    all_productTypes = MongoengineConnectionField(ProductType)
    all_catalogs = MongoengineConnectionField(Catalog)

schema = graphene.Schema(query=Query, types=[Product, ProductType, Catalog])