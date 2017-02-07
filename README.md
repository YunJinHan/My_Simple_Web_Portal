My Simple Web Portal
=============

Main Page
-------------
![main](./screenshot/main.png)

Around Me
-------------
If you click that store information, the corresponding shop location appears on the map.
![Around Me](./screenshot/aroundme.png)

IT info
-------------
Each minute brings forth the latest posting on the site.
Similarly, you can view the original content by clicking on the corresponding postings.
![IT info](./screenshot/info.png)


Scrapy Setting
=============
Crontab Setting
-------------
1. �͹̳ο��� EDITOR=vim crontab -e ����
2. ���� Code �ۼ�
<pre><codei>
MAILTO = '' // �ش� ����� ���� ����� �޾ƺ����� �ش� �����̸��� ������
PATH=/usr/local/bin
LC_CTYPE="utf-8" // �ѱ� ���ڵ� ����
* * * * * * cd crawling folder ���� ��� && scrapy crawl community
// �� �� ����. ���� * �� crontab ���� �󵵸� �������ش�.
</code></pre>
3. �Ϸ�

Database Setting
-------------
1. databse / createDB.sql �� ���̺� �� ������ ���̽� ����
2. crawling / pipeline.py ���� ȯ�濡 �°� ���� ����
3. �Ϸ�

API Setting
=============
1. index.php �� DAUM , NAVER API KEY ����
2. �Ϸ�

