My Simple Web Portal
=============

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

