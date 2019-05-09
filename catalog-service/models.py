
# flask_graphene_mongo/models.py
from datetime import datetime
from mongoengine import Document
from mongoengine.fields import (
    ReferenceField, StringField, IntField,ListField
)


class ProductType(Document):
    meta = {'collection': 'productType'}
    name = StringField()
    color = StringField()
    price = IntField()


class Product(Document):
    meta = {'collection': 'product'}
    prodType = ReferenceField(ProductType)
    quantity = IntField()


class Catalog(Document):
    meta = {'collection': 'catalog'}
    name = StringField()
    products = ListField(ReferenceField(Product))

