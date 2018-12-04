import startBrewing
from flask import Flask, request, jsonify
from flask_restful import Resource, Api
from flaskext.mysql import MySQL


app = Flask(__name__)
api = Api(app)

mysql.init_app(app)

class startBrew(Resource):
	def get(self, brewSettingsId):
		return {'brewSettingsId': startBrewing.get(brewSettingsId)}


api.add_resource(startBrew, '/startBrewing/<brewSettingsId>')

if __name__ == "__main__":
	#remove host for production
	app.run(host = '192.168.1.183')
