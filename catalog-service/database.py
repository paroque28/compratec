
from mongoengine import connect

from models.product import Product

CONECTION_STRING = 'mongodb://root:compratec@mongodb-primary:27017/admin'

# You can connect to a real mongo server instance by your own.
connect('graphene-mongo-example', host=CONECTION_STRING, alias='default')


def init_db():
    # Create the fixtures
    try:
        polo = Product(
            code='c001',
            name='Polo',
            color='blue',
            price=20,
            quantity=100)
        polo.save()

        jersey = Product(
            code='c002',
            name='Jersey',
            color='black',
            price=30,
            quantity=80)
        jersey.save()

        dressShirt = Product(
            code='c003',
            name='Dress Shirt',
            color='white',
            price=25,
            quantity=80)
        dressShirt.save()
    except BaseException:
        print("DB already initialized")
