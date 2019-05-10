# flask_graphene_mongo/schema.py
import graphene
from graphene_mongo import MongoengineObjectType
from models import Product as ProductModel


class Product(MongoengineObjectType):
    class Meta:
        model = ProductModel

class CreateProduct(graphene.Mutation):
    class Arguments:
        code = graphene.String()
        name = graphene.String()
        color = graphene.String()
        price = graphene.Int()
        quantity = graphene.Int()

    ok = graphene.Boolean()
    product = graphene.Field(lambda:Product)



    def mutate(self, info, code, name, color, price, quantity):
        product = ProductModel(code = code, name = name, color = color, price = price, quantity = quantity) 
        product.save()

        return CreateProduct(product=product)

class MyMutations(graphene.ObjectType):
    createProduct = CreateProduct.Field()

  
class Query(graphene.ObjectType):
    productByCode = graphene.Field(Product, code=graphene.String(required=True))
    allProducts = graphene.List(Product)

    def resolve_productByCode(_,info,code):
        return ProductModel.objects(code=code).first()
    
    def resolve_allProducts(_,info):
        return list(ProductModel.objects.all())


schema = graphene.Schema(query=Query, mutation=MyMutations)
