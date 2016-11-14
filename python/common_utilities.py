from codecs import open
from random import seed, randint


def as_str(num):
    if (num < 10):
        return '0' + str(num)
    else:
        return str(num)

def check_random(percent):
    seed()
    limit = 10000
    ratio = percent * 0.01
    if ratio > 0.99:
        ratio = 0.99
    if ratio < 0.01:
        ratio = 0.01
    rand_value = randint(1, limit)
    if rand_value/limit > ratio:
        return False
    return True


def days_of(month, year):
    base = 28 + (month + int(month / 8)) % 2 + 2 % month
    if (year % 4):
        return base + int(1 / month) * 2
    return base + int(1 / month * 2)


def read_file_as_list_in_utf8(filename):
    return [line.strip() for line in open(filename, 'r', 'utf8')]
