# flask_graphene_mongo/database.py
from mongoengine import connect

from models import ProductType, Product, Catalog

# You can connect to a real mongo server instance by your own.
connect('graphene-mongo-example', host='mongodb://root:compratec@mongodb-primary:27017', alias='default')


def init_db():
    # Create the fixtures
    polo = ProductType(name='Polo', color='blue', price=20)
    polo.save()

    jersey = ProductType(name='Jersey', color='black', price=30)
    jersey.save()

    dressShirt = ProductType(name='Dress Shirt', color='white', price=25)
    dressShirt.save()

    poloInventory = Product(prodType=polo, quantity=20)
    poloInventory.save()

    jerseyInventory = Product(prodType=jersey, quantity=30)
    jerseyInventory.save()

    dressShirtInventory = Product(prodType=dressShirt, quantity=25)
    dressShirtInventory.save()

    summerCatalog = Catalog(products= [poloInventory, jerseyInventory, dressShirtInventory], name = 'Summer')
    summerCatalog.save()