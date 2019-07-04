**GVim笔记（Debian 9.9 + Vim 8.0）**
```
sudo apt install vim-gtk
```
/etc/vim/vimrc
```
set nocompatible
syntax on
set number
set showmatch
set tabstop=4
set softtabstop=4
set shiftwidth=4
set expandtab
set nowrap
set autoread
set nobackup
set nowb
set noswapfile
set hlsearch
set incsearch
set guioptions-=T
set laststatus=2
set statusline=%F%m%r%h%w\ [%{&ff}]\ [%Y]\ [%l,%v][%p%%]\ %{strftime(\"%d/%m/%Y\ -\ %H:%M\")}
set backspace=2
set shortmess=atI
set fileencodings=utf-8
set fileencoding=utf-8
set encoding=utf-8
set visualbell
colorscheme torte
```
