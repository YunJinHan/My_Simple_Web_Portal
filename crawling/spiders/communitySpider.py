#-*-coding:utf-8

import scrapy
import re
from community.items import CommunityItem
from datetime import datetime

class CommunitySpider(scrapy.Spider):

	name = "community"

	def start_requests(self):
		for i in range(1,2):
			yield scrapy.Request("http://www.clien.net/cs2/bbs/board.php?bo_table=park&page=%d" % i, self.parse_clien)
			yield scrapy.Request("http://www.bobaedream.co.kr/list?code=freeb&page=%d" % i, self.parse_bobae)
			yield scrapy.Request("http://www.clien.net/cs2/bbs/board.php?bo_table=news&page=%d" % i, self.parse_clien2)

	def parse_clien(self,response):
		for sel in response.xpath('//tbody/tr[@class="mytr"]'):
			item = CommunityItem()
			item['source'] = u'클리앙'
			item['category'] = '자유게시판'
			item['title'] = sel.xpath('td[@class="post_subject"]/a/text()').extract()[0]
			item['url'] = 'http://clien.net/cs2' + sel.xpath('td[@class="post_subject"]/a/@href').extract()[0][2:]
			item['date'] = datetime.strptime(sel.xpath('td/span/@title').extract()[0],"%Y-%m-%d %H:%M:%S").strftime("%Y-%m-%d %H:%M:%S")

			td = sel.xpath('td')
			item['hits'] = int(td[4].xpath('text()').extract()[0])

			yield item

	def parse_clien2(self,response):
		for sel in response.xpath('//tbody/tr[@class="mytr"]'):
			item = CommunityItem()
			item['source'] = u'클리앙'
			item['category'] = 'IT소식'
			item['title'] = sel.xpath('td[@class="post_subject"]/a/text()').extract()[0]
			item['url'] = 'http://clien.net/cs2' + sel.xpath('td[@class="post_subject"]/a/@href').extract()[0][2:]
			item['date'] = datetime.strptime(sel.xpath('td/span/@title').extract()[0],"%Y-%m-%d %H:%M:%S").strftime("%Y-%m-%d %H:%M:%S")

			td = sel.xpath('td')
			item['hits'] = int(td[4].xpath('text()').extract()[0])

			yield item


	def parse_bobae(self,response):
		for sel in response.xpath('//tbody/tr[@itemtype="http://schema.org/Article"]'):
			item = CommunityItem()
			item['source'] = u'보배드림'
			item['category'] = '자유게시판'
			item['title'] = sel.xpath('td[@class="pl14"]/a/text()').extract()[0]
			item['url'] = "http://www.bobaedream.co.kr" + sel.xpath('td[@class="pl14"]/a/@href').extract()[0]

			date_now = datetime.now()
			date_tmp = sel.xpath('td[@class="date"]/text()').extract()[0]

			prag = re.compile('[0-9]{2}:[0-9]{2}')

			if prag.match(date_tmp):
				date_str = date_now.strftime('%y/%m/%d') + ' ' + date_tmp + ':00'
			else :
				date_str = date_now.strftime('%y/') + ' ' + date_tmp + '00:00:00'

			date = datetime.strptime(date_str,"%y/%m/%d %H:%M:%S")

			item['date'] = date.strftime("%Y-%m-%d %H:%M:%S")
			item['hits'] = int(sel.xpath('td[@class="count"]/text()').extract()[0])

			print '='*50
			print item['title']

			yield item
