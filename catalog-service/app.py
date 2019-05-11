
from db.database import init_db
from flask import Flask
from flask_graphql import GraphQLView
from schemas.schema import schema

app = Flask(__name__)


app.add_url_rule(
    '/',
    view_func=GraphQLView.as_view('graphql', schema=schema)
)

if __name__ == '__main__':
    init_db()
    app.run(host='0.0.0.0', port=80)
