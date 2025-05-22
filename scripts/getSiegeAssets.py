import os, requests, sys, unidecode
from bs4 import BeautifulSoup as bs

url = 'https://www.ubisoft.com/en-gb/game/rainbow-six/siege/game-info/operators'
r = requests.get(url)
soup = bs(r.content, 'html.parser')

def parse_node(node):
    card_node = node.select('.oplist__card__img')[0]
    operator_name = unidecode.unidecode(card_node['alt']).lower().strip()
    operator_portrait_src = card_node['src']

    icon_node = node.select('.oplist__card__icon')[0]
    operator_icon_src = icon_node['src']

    return {
        'operator_name': operator_name,
        'operator_portrait': operator_portrait_src,
        'operator_icon': operator_icon_src
    }

def get_operators_images():
    return [parse_node(node) for node in soup.select('.oplist__card')]


def downloadImage(img_src: str, filename: str, destination_folder: str, overwrite: bool = False):
    if not os.path.isdir(destination_folder):
        os.mkdir(destination_folder)

    response = requests.get(img_src)
    file_path = os.path.join(destination_folder, filename)

    if (not(overwrite) and os.path.isfile(file_path)):
        print(f'{file_path} already exists')
        return

    with open(file_path, mode="wb") as f:
        f.write(response.content)

    print(file_path)

    return


def download_operator_images(operator_list, overwrite: bool = False):
    parent_folder_path = os.path.dirname(os.getcwd())
    portraits_path = os.path.join(parent_folder_path, 'public', 'operatorPortraits')
    icons_path = os.path.join(parent_folder_path, 'public', 'operatorIcons');

    for operator in operator_list:
        filename = f'{operator['operator_name']}.png'
        downloadImage(operator['operator_portrait'], filename, portraits_path, overwrite)
        downloadImage(operator['operator_icon'], filename, icons_path, overwrite)
    return

if __name__ == "__main__":
    overwrite = (len(sys.argv) > 1 and sys.argv[1] == '-f')
    imgs = get_operators_images()
    download_operator_images(imgs, overwrite)
