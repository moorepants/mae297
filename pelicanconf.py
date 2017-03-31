#!/usr/bin/env python
# -*- coding: utf-8 -*- #
from __future__ import unicode_literals

import yaml

AUTHOR = u'Jason K. Moore, PhD'
SITENAME = u'MAE 297'
SITESUBTITLE = 'Mechanical and Aerospace Engineering Seminar'
SITEURL = ''

THEME = 'theme'
THEME_STATIC_DIR = 'static'
PATH = 'content'
STATIC_PATHS = ['headshots', 'images', 'js', 'css', 'fonts']
TIMEZONE = 'US/Pacific'

DEFAULT_DATE_FORMAT = ('%Y')

DEFAULT_LANG = u'en'
BOOTSTRAP_FILE = 'bootstrap.css'
CSS_FILE = 'freelancer.css'
FONTS = 'fonts'

SCRIPTS = [
    'jquery-1.11.0.js',
    'bootstrap.min.js',
    'http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js',
    'classie.js',
    'cbpAnimatedHeader.js',
    'jqBootstrapValidation.js',
    'contact_me.js',
    'freelancer.js',
    'attachment.js',
]

# Feed generation is usually not desired when developing
FEED_ALL_ATOM = None
CATEGORY_FEED_ATOM = None
TRANSLATION_FEED_ATOM = None

DIRECT_TEMPLATES = ['index']

# Top Menu Links
NAVLINKS = (
    ('#page-top', ''),
    ('#syllabus', 'Syllabus'),
    ('#portfolio', 'Speakers'),
)

# Portfolio Name
PORTFOLIO = 'Speaker Schedule'

SUMMARY_MAX_LENGTH = 100
