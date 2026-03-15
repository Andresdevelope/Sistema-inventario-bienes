# Sistema Inventario de Bienes

Aplicación web para el **registro, control, consulta y trazabilidad de bienes** institucionales.

## Descripción

Este sistema fue desarrollado en el contexto del **PNF Informática** para gestionar el inventario de bienes y sus movimientos, con control de acceso por roles y bitácora de acciones.

## ¿Qué hace el sistema?

- Registrar, editar, consultar y filtrar bienes.
- Organizar bienes por categorías y ubicaciones.
- Llevar bitácora de acciones críticas del sistema.
- Administrar usuarios y permisos.
- Mantener controles de seguridad en autenticación y autorización.

## Tecnologías

- **Backend:** Laravel 12, PHP 8.2+
- **Base de datos:** MySQL/MariaDB (según configuración)
- **Frontend:** Blade, Tailwind CSS, Vite

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js + npm
- Motor de base de datos compatible

## Instalación rápida

1. Clonar el repositorio.
2. Instalar dependencias de PHP y JS.
3. Copiar `.env.example` a `.env` (si aplica) y configurar base de datos.
4. Ejecutar migraciones.
5. Compilar assets o iniciar modo desarrollo.

También puedes usar el script de Composer `setup` definido en `composer.json`.

## Documentación interna

- Requerimientos y diagramas: `docs/diagramas/`
- Seguridad (hashing): `docs/seguridad/hashing-passwords.md`
- Roles y privilegios: `docs/privilegios_roles_sistema.txt`
- Constancia de revisión PI/licencia: `docs/propiedad_intelectual_sistema.md`

## Propiedad intelectual y licencia

- Este repositorio incluye licencia del proyecto en `LICENSE`.
- El aviso visual en interfaz (por ejemplo, `© 2026`) **no sustituye** un registro formal ante SAPI.
- Si se requiere cumplimiento institucional, anexar constancia oficial de registro (derecho de autor/marca, según corresponda).

## Créditos

Desarrollado por el equipo del **PNF Informática** para el proyecto **Sistema Inventario de Bienes**.

