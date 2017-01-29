# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html
import sys
import MySQLdb
import hashlib
from scrapy.exceptions import DropItem
from scrapy.http import Request

reload(sys)
sys.setdefaultencoding('utf-8')

class CommunityPipeline(object):

	host = '127.0.0.1'
	user = 'root'
	password = '0000'
	db = 'crawlingdb'
	words_to_filter = [u'19']

	def __init__(self):
		self.connection = MySQLdb.connect(self.host, self.user, self.password, self.db, charset="utf8", use_unicode=True)
		self.cursor = self.connection.cursor()
		self.cursor.execute("DELETE FROM board")

	def process_item(self,item,spider):

		for word in self.words_to_filter:
			if word in unicode(item['title']):
				raise DropItem("Contain forbidden word: %s" % word)
		else:
			try:
				self.cursor.execute("INSERT INTO board VALUES (%s, %s, %s, %s, %s, %s)",(item['source'],item['category'],item['title'],item['url'],item['hits'],item['date']))
				self.connection.commit()
			except MySQLdb.Error, e:
				print "Error %d: %s" % (e.args[0], e.args[1])
			return item
