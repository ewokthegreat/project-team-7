import json
import _mysql
from WordBank import setUpWordBank

obj_json = u'{"answer":[42], "abs":42}'

try:
    mysql_connection = _mysql.connect(user='',passwd='',
                                      host='',db='')
except mysql.connector.Error as err:
    print (err)


class FBTextPost(object):
    def __init__(self, JSONObject):
        self.__dict__ = json.loads(obj_json)

fbTest = FBTextPost(obj_json)

setUpWordBank([],'../doc/SpiderWordBank.csv')






print (fbTest.answer)
print (fbTest.abs)