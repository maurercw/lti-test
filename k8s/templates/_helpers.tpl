{{/* vim: set filetype=mustache: */}}
{{/*
Expand the name of the chart.
*/}}
{{- define "helm-k8s-php-deployer.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "helm-k8s-php-deployer.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Common labels
*/}}
{{- define "helm-k8s-php-deployer.labels" -}}
helm.sh/chart: {{ include "helm-k8s-php-deployer.chart" . }}
{{ include "helm-k8s-php-deployer.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- if .Values.image.tagName }}
app.kubernetes.io/image-tag: {{ .Values.image.tagName }}
{{- end }}
{{- end -}}

{{/*
Selector labels
*/}}
{{- define "helm-k8s-php-deployer.selectorLabels" -}}
app.kubernetes.io/name: {{ include "helm-k8s-php-deployer.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end -}}