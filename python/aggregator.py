import os
import re

class project:
    def __init__(self):
        os.chdir('..')
        self.path = os.getcwd()
        self.exclude_patterns = ['\\.', '__', 'Tools', 'Tests']
        self.binary_ext = ['.png', '.jpg', '.ttf']

class directory:
    def __init__(self, path, files):
        self.path = path
        self.files = files

    def get_subdirs_from_path(self):
        return re.split(r"""\\""", self.path)[1:-1]

    def cut_the_path(self, path_to_cut):
        self.path = self.path[len(path_to_cut):]


def match_filter_pattern(string, patterns):
    for pattern in patterns:
        if pattern in string:
            return True
    return False


def get_project_hierarchy(current_project):
    hierarchy = []
    current_dir = current_project.path
    unused_file_patterns = current_project.exclude_patterns
    for (dir_path, dir_names, file_names) in os.walk(current_dir):

        if match_filter_pattern(dir_path, unused_file_patterns):
            continue

        if dir_path[-1:] != '\\':
            dir_path += '\\'

        dir_for_append = directory(dir_path, file_names)
        dir_for_append.cut_the_path(current_dir)

        hierarchy.append(dir_for_append)
    return hierarchy


def print_hierarchy_summary(hierarchy):
    num_of_files = num_of_dirs = 0
    for struct in hierarchy:
        print(struct.path)
        if struct.files:
            num_of_dirs += 1
            for file in struct.files:
                num_of_files += 1
                print('\t', file)
    print('Processed ' + str(num_of_files) +
          ' files in ' + str(num_of_dirs) + ' directories')
