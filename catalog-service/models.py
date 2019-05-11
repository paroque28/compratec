
# flask_graphene_mongo/models.py
from mongoengine import Document
from mongoengine.fields import (
    StringField, IntField
)


class Product(Document):
    meta = {'collection': 'product'}
    code = StringField(unique=True)
    name = StringField()
    color = StringField()
    price = IntField()
    quantity = IntField()
