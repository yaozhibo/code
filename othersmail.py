# -*- coding:utf-8 -*-

import sys
import re
import requests
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from email.header import Header
from email.utils import formataddr

sender = ''
receivers = ''

# 创建一个带附件的实例
message = MIMEMultipart()
message['From'] = formataddr(["Json", sender])
message['To'] = formataddr(["Client", receivers])
subject = 'fuck'
message['Subject'] = Header(subject, 'utf-8')

# 邮件正文内容
message.attach(MIMEText(
    '你好，草泥马，你是煞笔吗'
    , 'plain'
    , 'utf-8'))

# att1 = MIMEText(open('hl.txt', 'rb').read(), 'base64', 'utf-8')
# att1["Content-Type"] = 'application/octet-stream'
# 这里的filename可以任意写，写什么名字，邮件中显示什么名字
# att1["Content-Disposition"] = 'attachment; filename="hl.txt"'
# message.attach(att1)

# 第三方 SMTP 服务
mail_host = ""  # 设置服务器
mail_pass = ""  # 口令
try:
    smtpObj = smtplib.SMTP_SSL(mail_host, 465)
    smtpObj.login(sender, mail_pass)
    smtpObj.sendmail(sender, receivers, message.as_string())
    smtpObj.quit()
    print("邮件发送成功")
except smtplib.SMTPException:
    print("Error: 无法发送邮件")
