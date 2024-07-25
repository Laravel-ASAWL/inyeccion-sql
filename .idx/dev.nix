{pkgs}: {
  channel = "stable-23.11";
  packages = [
    pkgs.php82
    pkgs.php82Packages.composer
    pkgs.nodejs_20
  ];
  idx = {
    extensions = [
      "amirmarmul.laravel-blade-vscode"
      "bmewburn.vscode-intelephense-client"
      "qwtel.sqlite-viewer"
      "rangav.vscode-thunder-client"
    ];
    workspace = {
      onCreate = {
        default.openFiles = [
          "README.md"
        ];
      };
    };
  };
}